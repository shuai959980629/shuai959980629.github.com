<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @风控审核model
 * @author zhoushuai
 * @category 20160518
 * @version
 */
class Wind_bill_model extends Base_model
{

    public function __construct()
    {
        parent::__construct('wind_bill');
    }

    /**
     * 添加审核。
     */
    public function add($param){
        $wid = 0;
        $where = array(
            'aid'       => $param['aid'],
            'uid'       => $param['uid'],
        );
        $wind = $this->getWidgetRow($where);
        if(!empty($wind)){
            $wid = $wind['id'];
        }
        $result = $this->save($param,$wid);
        return $result;
    }



}