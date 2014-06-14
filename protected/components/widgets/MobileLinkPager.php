<?php

/**
 *
 * @author Su Dunkuai <sudk@trunkbow.com>
 * @since 1.0
 */
class MobileLinkPager extends CBasePager
{

    public $id;
    public $page_num;
    public $total_num;
    public $num_of_page;
    public $condition;
    public $order;
    public $url;

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init()
    {
    if($this->page_num!=ceil($this->total_num/$this->num_of_page)&&$this->total_num!=0){
        echo <<<EOF
    <a class="list-group-item row" id='page-toolbar' onclick='{$this->id}.refresh({$this->page_num}+1)' >
        <h5  class="text-center" id='load-more'>加载更多</h5>
    </a>
EOF;
            if($this->page_num==1){
                $this->buildScript();
            }
        }

    }

    public function buildScript(){
        echo <<<EOF
<script type="text/javascript">

if({$this->id}=== undefined||!{$this->id}.hasOwnProperty('url')) {

		var {$this->id} = {};
		    {$this->id}.page = $this->page_num;
		    {$this->id}.condition = "$this->condition";
		    {$this->id}.order = "$this->order";
		    {$this->id}.url = "$this->url";
		    {$this->id}.refresh = function(page) {
			if( typeof page == "undefined" ) page = this.page;
			url = this.url+"&page="+this.page+"&"+this.condition+"&q_order="+this.order;

			url=encodeURI(url);
			jQuery.ajax({
			type: "get",
			url: url,
			beforeSend: function(XMLHttpRequest){
				$('#load-more').html('我在努力加载...');
			},
			success: function(data, textStatus){
			    $('#page-toolbar').remove();
				jQuery("#$this->id").append(data);
			},
			complete: function(XMLHttpRequest, textStatus){
				//hideLoadingLayer();
			},
			error: function(){
				alert("请求失败");
			}
			});
		};
	}else{
	    $this->id.page = $this->page_num;
	    $this->id.order = '$this->order';
	}
</script>
EOF;

    }
}
