<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @评估model
 * @author zhoushuai
 * @category 20160518
 * @version
 */
class Evaluate_bill_model extends Base_model
{

    public function __construct()
    {
        parent::__construct('evaluate_bill');
    }

    /*扩展数据字段说明*/
    public $car_info = array(
        'assess' => array(
            /*未来两年的价格预测*/
            'trend' => array(
                'trend_year'    =>'趋势价格年份',
                'eval_price'    =>'该年车商收购价',
            ),
            /*当前车型基本估值*/
            'eval_prices' => array(
                'c2b_price'     =>'收车价',             //精确估价
                'b2b_price'     =>'同业交易价',
                'b2c_price'     =>'零售价',
            ),
            /*当前车型信息*/
            'model_info' => array(
                'liter'             => '该车辆的排量',
                'gear_type'         => '该车辆的变数箱',
                'highlight_config'  => '该车辆的亮点配置',
            ),
            'color'             => '该车辆的颜色',
            'interior'          => '该车辆的内饰状况',
            'surface'           => '该车辆的漆面状况',
            'work_state'        => '该车辆的工况状况',
            'pro_date'          => '该车辆的出厂日期',
            'vpoints'           => '该车辆的违章分数',
            'violation'         => '该车辆有几个12分',
            'fine'              => '该车辆罚款过的金额',
            'carkey'            => '该车辆有几把钥匙',
            'pledge'            => '该车辆的状况，不确定，已抵押，未抵押',
            'stat'              => '该车辆的状况，不确定，正常，查封，锁定',
            'takeoffon'         => '该车辆是否脱审，1表示是，0表示否',
            'annualaudit'       => '该车辆的年审日期',
            'highlight'         => '该车辆添加的亮点配置',
            'car_credentials'   => '车辆登记证',
        ),
    );

}
