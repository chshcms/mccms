<?php
/*
'软件名称：漫城CMS（Mccms）
'官方网站：http://www.mccms.cn/
'软件作者：桂林崇胜网络科技有限公司（By:烟雨江南）
'--------------------------------------------------------
'Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
'遵循Apache2开源协议发布，并提供免费使用。
'--------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mcdb extends CI_Model{

    function __construct (){
		parent:: __construct ();
		//加载数据库连接
		$this->load->database();
		//加载模版
		$this->load->get_templates();
	}

    //SQL语句查询
    function get_sql($sql,$arr=0){
        $query=$this->db->query($sql);
	    if($arr==0){
            return $query->result();
	    }else{
            return $query->result_array();
	    }
	}

    //SQL语句查询总数量
    function get_sql_nums($sql='')  {
        if(!empty($sql)){
		    preg_match('/select\s*(.+)from/i', strtolower($sql),$sqlarr);
		    if(!empty($sqlarr[1])){
               $sql=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sql));
			   $rows=$this->db->query($sql)->result_array();
			   $nums=(int)$rows[0]['counta'];
		    }else{
			   $query=$this->db->query($sql);
			   $nums=(int)$query->num_rows();
		    }
        }else{
           $nums=0;
        }
        return $nums;
	}

    //查询总数量
    function get_nums($table,$arr='',$like=''){
        if($arr){
            foreach($arr as $k=>$v){
			    if(strpos($v,',') !== false && preg_match('/^([0-9]+[,]?)+$/', $v)){
				    $v = explode(',',$v);
                    $this->db->where_in($k,$v); //条件
				}elseif(substr($v,0,3)=='or|'){
					$this->db->or_where($k,substr($v,3)); //条件
			    }else{
                    $this->db->where($k,$v); //条件
			    }
		    }
        }
        if($like){
            foreach ($like as $k=>$v){
               $this->db->like($k,$v); //搜索条件
		    }
        }
        $this->db->select('count(*) as count');
	    $rows = $this->db->get($table)->row_array();
	    $nums = (int)$rows['count'];
        return $nums;
	}

    //查询字段总和
    function get_sum($table,$zd,$arr='',$like=''){
        if($arr){
            foreach($arr as $k=>$v){
			    if(strpos($v,',') !== false && preg_match('/^([0-9]+[,]?)+$/', $v)){
				    $v = explode(',',$v);
                    $this->db->where_in($k,$v); //条件
				}elseif(substr($v,0,3)=='or|'){
					$this->db->or_where($k,substr($v,3)); //条件
			    }else{
                    $this->db->where($k,$v); //条件
			    }
		    }
        }
        if($like){
            foreach ($like as $k=>$v){
               $this->db->like($k,$v); //搜索条件
		    }
        }
        $this->db->select('sum('.$zd.') as num');
        $query=$this->db->get($table);
	    $rows=$query->row_array();
	    $nums=(int)$rows['num'];
        return $nums;
	}

    //按条件查询单一对象
    function get_row($table,$fzd='*',$arr='',$order=''){
        if(is_array($arr)){
            foreach($arr as $k=>$v){
				if(strpos($v,',') !== false && preg_match('/^([0-9]+[,]?)+$/', $v)){
					$v = explode(',',$v);
	                $this->db->where_in($k,$v); //条件
				}else{
	                $this->db->where($k,$v); //条件
				}
			}
        }else{
             $this->db->where('id',$arr);
		}
        $this->db->select($fzd);
	    if($order != '') $this->db->order_by($order); //排序
	    $query=$this->db->get($table);
	    return $query->row();
	}

    //按条件查询单一数组
    function get_row_arr($table,$fzd='*',$arr='',$order=''){
        if(is_array($arr)){
            foreach ($arr as $k=>$v){
				if(strpos($v,',') !== false && preg_match('/^([0-9]+[,]?)+$/', $v)){
					$v = explode(',',$v);
	                $this->db->where_in($k,$v); //条件
				}else{
	                $this->db->where($k,$v); //条件
				}
			}
        }else{
            $this->db->where('id',$arr);
		}
	    $this->db->select($fzd);
	   	if($order != '') $this->db->order_by($order); //排序
	    $query=$this->db->get($table);
	    return $query->row_array();
	}

    //生成查询列表结果，带分页
    function get_select($table,$fzd='*',$arr='',$order='id DESC',$limit='15',$like=''){
		if($arr){
			foreach ($arr as $k=>$v){
				if(strpos($v,',') !== false && preg_match('/^([0-9]+[,]?)+$/', $v)){
					$v = explode(',',$v);
					$this->db->where_in($k,$v); //条件
				}elseif(substr($v,0,3)=='or|'){
					$this->db->or_where($k,substr($v,3)); //条件
				}else{
					$this->db->where($k,$v); //条件
				}
			}
		}
		if($like){
			foreach ($like as $k=>$v){
				if(substr($v,0,3)=='or|'){
					$this->db->or_like($k,substr($v,3)); //条件
				}else{
					$this->db->like($k,$v); //搜索条件
				}
			}
		}
		$this->db->select($fzd); //查询字段
		if(is_array($limit)){
			$this->db->limit($limit[0],$limit[1]);  //分页
		}else{
			$this->db->limit($limit);  //分页
		}
		if(is_array($order)){
			for($i=0; $i < sizeof($order)/2; $i++) {
				$this->db->order_by($order[2*$i],$order[2*$i+1]);
			}
		}else{
			$this->db->order_by($order); //排序
		}
		$query=$this->db->get($table); //查询表
		return $query->result_array();
	}

    //增加
    function get_insert($table,$arr){
        if($arr){
	        $this->db->insert($table,$arr);
            $ids = $this->db->insert_id();
		    return $ids;
        }else{
		    return false;
        }
	}

    //修改
    function get_update($table,$id,$arr,$zd='id'){
        if(!empty($id)){
	        if(is_array($id)){
		        $this->db->where_in($zd,$id);
	        }else{
		        $this->db->where($zd,$id);
	        }
	        if($this->db->update($table,$arr)){
	            return true;
            }else{
		        return false;
            }
        }else{
		    return false;
        }
    }

    //删除
    function get_del ($table,$ids,$zd='id'){
        if(is_array($ids)){
	        $this->db->where_in($zd,$ids);
        }else{
	        $this->db->where($zd,$ids);
        }
        if($this->db->delete($table)){
            return true;
        }else{
	        return false;
        }
	}
}