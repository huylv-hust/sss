<?php

namespace app\models;

use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;

class ParentDiscount extends ActiveRecord
{
    public static function tableName()
    {
        return 'PARENT_DISCOUNT';
    }

    private $return_code = [
        'FAIL' => 2,
        'USED' => 1,
        'SUCCESS' => 0,
    ];

    public function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

        $login_info = \Yii::$app->session->get('login_info');
        if (isset($login_info['M50_SS_CD']) && $login_info['M50_SS_CD'] != '') {
            $query->andWhere('SS_CD =:ss_cd', [':ss_cd' => $login_info['M50_SS_CD']]);
        }

        if (isset($filters['ID']) && $filters['ID']) {
            $query->andwhere('ID = ' . $filters['ID']);
        }

        if (isset($filters['offset']) && $filters['offset']) {
            $query->offset($filters['offset']);
        }

        if (isset($filters['limit']) && $filters['limit']) {
            $query->limit($filters['limit']);
        }

        return $query;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('ID DESC');
        return $query->all();
    }

    public function setData($data = [])
    {
        foreach ($this->attributes as $k => $v) {
            $this->{$k} = isset($data[$k]) && trim($data[$k]) != '' ? trim($data[$k]) : $v;
        }
    }

    public function countData($filters = [], $select = 'ID')
    {
        $query = $this->getWhere($filters, $select);
        return $query->count();
    }

    public function saveData($parent, $package, $discount, $discount_remove, $package_remove)
    {
        $transaction = $this->getDb()->beginTransaction();
        try {
            $this->setData($parent);
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }
            $parent_id = $this->ID;

            //remove packages
            $delete = DiscountPackages::deleteAll('ID IN (' . $package_remove . ')');
            if (!isset($delete)) {
                $transaction->rollBack();
                return false;
            }
            //remove discount after package removed
            $delete = Discounts::deleteAll('DISCOUNT_PACKAGES_ID IN (' . $package_remove . ')');
            if (!isset($delete)) {
                $transaction->rollBack();
                return false;
            }
            //remove discount
            $delete = Discounts::deleteAll('ID IN (' . $discount_remove . ')');
            if (!isset($delete)) {
                $transaction->rollBack();
                return false;
            }

            //insert-update
            foreach ($package as $k => $v) {
                if ($v['NAME'] === '') {
                    continue;
                }

                $package_data['NAME'] = $v['NAME'];
                $package_data['PARENT_DISCOUNT_ID'] = $parent_id;
                //insert-update package
                $obj_package = new DiscountPackages();
                if ($v['ID']) {
                    //insert
                    $obj_package = DiscountPackages::findOne($v['ID']);
                }
                $obj_package->setAttributes($package_data, false);
                if (!$obj_package->save()) {
                    $transaction->rollBack();
                    return false;
                }
                $package_id = $obj_package->ID;
                //insert-update discount
                $discount[$k] = isset($discount[$k]) ? $discount[$k] : [];
                foreach ($discount[$k] as $key => $value) {
                    if (trim($value['DESCRIPTION']) === '' && trim($value['VALUE']) === '') {
                        continue;
                    }
                    $value['DISCOUNT_PACKAGES_ID'] = $package_id;
                    $obj_discount = new Discounts();
                    if ($value['ID']) {
                        //insert
                        $obj_discount = Discounts::findOne($value['ID']);
                    }
                    $obj_discount->setAttributes($value, false);
                    if (!$obj_discount->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }


    public function deleteData($id)
    {
        $denpyo_inspection_obj = new DenpyoInspection();
        $transaction = $this->getDb()->beginTransaction();
        $list_id[] = 0;
        try {
            //check parent discount in use
            $check_parent_used = empty($denpyo_inspection_obj->getData(['PARENT_DISCOUNT_ID' => $id])) ? true : false;
            if (!$check_parent_used) {
                return $this->return_code['USED'];
            }

            $package_id = DiscountPackages::findAll(['PARENT_DISCOUNT_ID' => $id]);
            foreach ($package_id as $v) {
                $list_id[] = $v['ID'];
            }
            //check discount in use
            $check_discount_used = empty($denpyo_inspection_obj->getData(['DISCOUNTS' => $list_id])) ? true : false;
            if (!$check_discount_used) {
                return $this->return_code['USED'];
            }

            $delete = Discounts::deleteAll('DISCOUNT_PACKAGES_ID IN (' . implode(',', $list_id) . ')');
            if (!isset($delete)) {
                return $this->return_code['FAIL'];
            }

            $delete = DiscountPackages::deleteAll(['PARENT_DISCOUNT_ID' => $id]);
            if (!isset($delete)) {
                $transaction->rollBack();
                return $this->return_code['FAIL'];
            }

            $delete = $this->deleteAll(['ID' => $id]);
            if (!isset($delete)) {
                $transaction->rollBack();
                return $this->return_code['FAIL'];
            }

            $transaction->commit();
            return $this->return_code['SUCCESS'];

        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return $this->return_code['FAIL'];
    }

    public function getSeq()
    {
        $command = \Yii::$app->db->createCommand('SELECT PARENT_DISCOUNT_SEQ.nextval FROM dual');
        $res = $command->queryAll();
        return $res['0']['NEXTVAL'];
    }

    public function beforeSave($insert)
    {
        $this->UPDATED_AT = new Expression("CURRENT_DATE");
        if (!$this->ID) {
            $this->CREATED_AT = new Expression("CURRENT_DATE");
            $this->ID = $this->getSeq();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
