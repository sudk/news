<?php

/**
 * @author sudk
 */
class News extends CActiveRecord {

    const TYPE_1=1;
    const TYPE_2=2;
    const TYPE_3=3;
    const TYPE_4=4;

    const TYPE_5=5;
    const TYPE_6=6;
    const TYPE_7=7;

    const TYPE_8=8;
    const TYPE_9=9;
    const TYPE_10=10;
    const TYPE_11=11;

    const TYPE_12=12;
    const TYPE_13=13;
    const TYPE_14=14;
    const TYPE_15=15;
    const TYPE_16=16;
    const TYPE_17=17;
    const TYPE_18=18;
    const TYPE_19=19;

    const To_Top=1;
    const Not_To_Top=0;

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

    public static function ToTop($to_top=null){
        $ar=array(
            self::To_Top=>"YES",
            self::Not_To_Top=>"NO"
        );
        return $to_top === null ? $ar : $ar[$to_top];
    }

    /**
     **/

    public static function GetType($type=null){
        $ar=array(
            ''=>"---请选择栏目---",
            self::TYPE_1=>"News-Journal",
            self::TYPE_2=>"News-Dynamic",
            self::TYPE_3=>"IAUTV-City",
            self::TYPE_4=>"IAUTV-Gourmet",
            self::TYPE_5=>"IAUTV-Australia Life",
            self::TYPE_6=>"IAUTV-Directory",
            self::TYPE_7=>"IAUTV-In Australia",
            self::TYPE_8=>"Business-City Propaganda",
            self::TYPE_9=>"Business-Attract Investment",
            self::TYPE_10=>"Business-Promote",
            self::TYPE_11=>"Business-Tourism Culture",
            self::TYPE_12=>"Australia-Study Abroad",
            self::TYPE_13=>"Australia-Healthy",
            self::TYPE_14=>"Australia-Traveling",
            self::TYPE_15=>"Australia-Food",
            self::TYPE_16=>"Australia-Trend",
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