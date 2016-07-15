<?php

/**
 * 账户余额日志表
 *
 * @author baihua <baihua_2011@163.com>
 */
class AccountLog extends CActiveRecord {

    private $user = null;
    private $eventType = null;
    private $amountData = 0;
    private $config = null;
    private $noteData = "";
    private $allow_event = array(
        'recharge' => 1, //充值到余额
        'withdraw' => 2, //从余额提现
        'pay' => 3, //从余额支付
        'drawback' => 4, //退款到余额
    );

    //写入日志并且更新账户余额
    public function write($config) {
        try {
            //设置用户信息
            if (isset($config['user_id'])) {
                $this->setUser($config['user_id']);
            } else {
                throw new Exception("用户信息不存在");
            }
            //设置操作类别
            isset($config['event']) ? $this->setEvent($config['event']) : "";
            //设置金额
            if (isset($config['amount'])) {
                $this->setAmount($config['amount']);
            } else {
                throw new Exception("金额必须大于0元");
            }
            //设置公共信息
            $this->config = $config;
            //生成note信息
            $this->noteData = isset($config['note']) ? $config['note'] : $this->note();
            //写入数据库
            $finnalAmount = $this->user['balance'] + $this->amountData;
            if ($finnalAmount < 0) {
                throw new Exception("用户余额不足");
            }
            $db = Yii::app()->db;
            $dbTrans = $db->beginTransaction();
            try {
                //对用户余额进行更新
                $memberModel = new Member();
                $updateBalance = $memberModel->updateBalance($finnalAmount, $this->user['id']);
                if (!$updateBalance) {
                    throw new Exception("用户余额数据更新失败");
                }
                //记录用户余额变动记录
                $accountLogModel = new AccountLog();
                $accountLogModel->user_id = $this->user['id'];
                $accountLogModel->event = $this->allow_event[$this->eventType];
                $accountLogModel->note = $this->noteData;
                $accountLogModel->amount = $this->amountData;
                $accountLogModel->amount_log = $finnalAmount;
                $accountLogModel->type = $this->amountData >= 0 ? 0 : 1;
                $accountLogModel->time = date('Y-m-d H:i:s');
                $accountLogModel->save();
                $dbTrans->commit();
                return true;
            } catch (Exception $e) {
                $dbTrans->rollback();
                return '用户余额更新失败';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 设置用户信息
     * @param type $userId
     * @throws Exception
     */
    private function setUser($userId) {
        $model = new User();
        $userInfo = $model->find(array(
            'select' => array('u.id', 'u.username', 'm.balance'),
            'condition' => 'u.id=:userId',
            'params' => array(':userId' => intval($userId)),
            'alias' => 'u',
            'join' => 'left join {{member}} m on u.id=m.user_id'
        ));
        if (empty($userInfo)) {
            throw new Exception("用户信息不存在");
        } else {
            $this->user = $userInfo;
        }
    }

    /**
     * 设置操作类别
     * @param type $eventKey
     * @throws Exception
     */
    private function setEvent($eventKey) {
        if (!isset($this->allow_event[$eventKey])) {
            throw new Exception("事件未定义");
        } else {
            $this->eventType = $eventKey;
        }
    }

    /**
     * 设置金额
     * @param type $amount
     * @throws Exception
     */
    private function setAmount($amount) {
        if (is_numeric($amount)) {
            $this->amountData = (round($amount, 2));

            //金额正负值处理
            if (in_array($this->allow_event[$this->eventType], array(2, 3))) {
                $this->amountData = '-' . ($this->amountData);
            }
        } else {
            throw new Exception("金额必须大于0元");
        }
    }

    /**
     * 生成note信息
     * @return type
     * @throws Exception
     */
    private function note() {
        $note = "";
        switch ($this->eventType) {
            //支付
            case 'pay': {
                    $note .= "用户[{$this->user['id']}]{$this->user['username']}使用余额支付购买，订单[{$this->config['order_no']}]，金额：{$this->amountData}元";
                }
                break;
            default: {
                    throw new Exception("未定义事件类型");
                }
        }
        return $note;
    }

    /**
     * model 的静态方法
     * @param type $className
     * @return type
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 对应表名
     * @return string
     */
    public function tableName() {
        return '{{account_log}}';
    }

}
