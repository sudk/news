<?php

/**
 * @author sudk
 */
class News extends CActiveRecord {

    const TYPE_N1=1;
    const TYPE_N2=2;
    const TYPE_N3=3;
    const TYPE_N4=4;

    const TYPE_F1=5;
    const TYPE_F2=6;
    const TYPE_F3=7;

    const TYPE_P1=8;
    const TYPE_P2=9;
    const TYPE_P3=10;
    const TYPE_P4=11;

    const TYPE_Q1=12;
    const TYPE_Q2=13;
    const TYPE_Q3=14;
    const TYPE_Q4=15;
    const TYPE_Q5=16;
    const TYPE_Q6=17;
    const TYPE_Q7=18;
    const TYPE_Q8=19;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'news';
    }

    public function rules() {
        return array(
            //安全性
            array('news_id,title,summary,content,type,author,public_date,record_time,to_top,creator,lon_lat,addr', 'safe', 'on' => 'create'),
            array('news_id,title,summary,content,type,author,public_date,record_time,to_top,creator,lon_lat,addr', 'safe', 'on' => 'modify'),
        );
    }

    /**
    电子报--热点、文体、小说、应用
    澳洲风情--民俗、文化、风光
    魅力山东--城市宣传、招商引资、企业推广、旅游文化
    澳洲指南--教育、留学、旅游、美食、交通、酒店、医院、求救电话：000
     **/

    public static function GetType($type=null){
        $ar=array(
            ''=>"---请选择类型---",
            self::TYPE_N1=>"电子报-热点",
            self::TYPE_N2=>"电子报-文体",
            self::TYPE_N3=>"电子报-小说",
            self::TYPE_N4=>"电子报-应用",

            self::TYPE_F1=>"澳洲风情-民俗",
            self::TYPE_F2=>"澳洲风情-文化",
            self::TYPE_F3=>"澳洲风情-风光",

            self::TYPE_P1=>"魅力山东-城市宣传",
            self::TYPE_P2=>"魅力山东-招商引资",
            self::TYPE_P3=>"魅力山东-企业推广",
            self::TYPE_P4=>"魅力山东-旅游文化",

            self::TYPE_Q1=>"澳洲指南-教育",
            self::TYPE_Q2=>"澳洲指南-留学",
            self::TYPE_Q3=>"澳洲指南-旅游",
            self::TYPE_Q4=>"澳洲指南-美食",
            self::TYPE_Q5=>"澳洲指南-交通",
            self::TYPE_Q6=>"澳洲指南-酒店",
            self::TYPE_Q7=>"澳洲指南-医院",
            self::TYPE_Q8=>"澳洲指南-求救电话",
        );
        return $type === null ? $ar : $ar[$type];
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = ' 1=1 ';
        $params = array();

        if ($args['creator'] != '') {
            $condition.=' AND creator=:creator';
            $params['creator'] = $args['creator'];
        }

        if ($args['title'] != '') {
            $condition.=' AND title LIKE :title';
            $params['title'] = $args['title'] . '%';
        }

        if ($args['type'] != '') {
            $condition.=' AND type=:type';
            $params['type'] = $args['type'];
        }

        $total_num = News::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $criteria->order = 'to_top DESC,record_time DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $criteria->order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $criteria->order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;

        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = News::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($pages->currentPage + 1);
        $rs['total_num'] = $total_num;
        $rs['total_page'] = ceil($rs['total_num'] / $rs['page_num']);
        $rs['num_of_page'] = $pages->pageSize;
        $rs['url'] = "./?r=mobile/news/grid";
        $rs['rows'] = $rows;

        return $rs;

    }

}