<?php
use Phalcon\Paginator\AdapterInterface as PaginatorInterface;

/**
 * 分页器，实现 phalcon 接口，以工厂方法进行实例化
 * 如：ToolPaginator::factory(300, 8);
 * @author lideqiang
 *
 */
class TPaginator implements PaginatorInterface
{
	
	/**
	 * 当页面
	 * 
	 * @var unknown
	 */
	public $page = 0;
	
	/**
	 * 页面尺寸
	 * 
	 * @var unknown
	 */
	public $pagesize = 0;
	
	/**
	 * 默认页面尺寸
	 * 
	 * @var unknown
	 */
	public $defaultPagesize = 20;
	
	/**
	 * 数据总数
	 * 
	 * @var unknown
	 */
	public $total = 0;
	
	/**
	 * 总页数
	 * 
	 * @var unknown
	 */
	public $totalPage = 0;
	
	/**
	 * 前一页
	 * 
	 * @var unknown
	 */
	public $before = 0;
	
	/**
	 * 后一页
	 * 
	 * @var unknown
	 */
	public $next = 0;
	
	/**
	 * 最后一页
	 * 
	 * @var unknown
	 */
	public $last = 0;

	/**
	 * 前后显示页数
	 * @var unknown
	 */
	public $beforePageNum = 4;

	/**
	 * 请求URI
	 * @var unknown
	 */
	public $uri = '';

	/**
	 * 工厂方法，实例化分类对象
	 * @param number $total
	 * @param number $pagesize
	 */
	public static function factory($total = 0, $pagesize = 20) {
		return new self(array('total' => $total, 'pagesize' => $pagesize) );
	}

	/**
	 * 构造器，继承
	 * @param array $config
	 * @return NULL|ToolPaginator
	 */
	public function __construct(array $config) {
		if(!(isset($config['pagesize']) && isset($config['total']))) {
			return null;
		}
		$this->total = $config['total'] > 0 ? $config['total'] : 0;
		$this->pagesize = $config['pagesize'] > 0 ? $config['pagesize'] : $this->defaultPagesize;
		$this->getUri();
		$this->reset ();
		return $this;
	}

	/**
	 * 获取URI
	 */
	public function getUri() {
		$uri = substr($_SERVER['QUERY_STRING'], 5);
		
		preg_match_all('/(page\/.*?)$/', $uri, $matches);
		preg_match_all('/(page\/.*?)\//', $uri, $matches2);
		if($matches[1]) {
			$this->uri = str_replace('/'.$matches[1][0], '', $uri);
		} elseif($matches2[1]) {
			$this->uri = str_replace($matches2[1][0].'/', '', $uri);
		} else {
			$this->uri = $uri;
		}

		$this->page = $matches[1] ? intval(substr($matches[1][0], 5)) : 1;
		return $this;
	}

	/**
	 * 重新进行分页计算
	 */
	protected function reset()
	{
		$this->totalPage = ceil ( $this->total / $this->pagesize );
		$this->before = $this->page > 1 ? ($this->page < $this->totalPage ? ($this->page - 1) : ($this->totalPage - 1)) : 0;
		$this->next = $this->page < $this->totalPage ? ($this->page > 1 ? ($this->page + 1) : 2) : 0;
		$last = $this->totalPage;
		return $this;
	}
	
	/**
	 * 设置当前页
	 * 
	 * @param unknown $page        	
	 */
	public function setCurrentPage($page)
	{
		$this->page = $page > $this->totalPage ? $this->totalPage : $page;
		$this->reset ();
		return $this;
	}

	public function getCurrentPage() {
		return $this->page;
	}

	/**
	 * 设置前置页面数
	 * @param number $beforePageNum
	 */
	public function setBeforePageNum($beforePageNum = 3) {
		$this->beforePageNum = $beforePageNum;
		return $this;
	}

	/**
	 * 设置页面尺寸
	 * 
	 * @param number $pagesize        	
	 * @return ToolPaginator
	 */
	public function setLimit($pagesize = 20)
	{
		$this->pagesize = $pagesize > 0 ? $pagesize : $this->defaultPagesize;
		$this->reset ();
		return $this;
	}
	
	/**
	 * 获取页面尺寸
	 * 
	 * @return number
	 */
	public function getLimit()
	{
		return $this->pagesize;
	}
	
	/**
	 * 获取分页对象
	 */
	public function getPaginate()
	{
		$pageObj = new stdClass();
		$pageObj->items = array();
		$pageObj->current = $this->page;
		$pageObj->before = $this->before;
		$pageObj->next = $this->next;
		$pageObj->last = $this->last;
		$pageObj->total_pages = $this->totalPage;
		$pageObj->total_items = array();
		return $pageObj;
	}
	
	/**
	 * 获取分页HTML
	 */
	public function getHtml()
	{
		$html = '';
		
		$start = 1;
		$beforeLimit = $this->page - $this->beforePageNum;
		if($beforeLimit <= 0) {
			$beforeHtml = $this->page == 1 ? '<span class="prev over">上一页</span>' : '<span class="prev"><a href="'.$this->uri.'/page/'.($this->page - 1).'" class="a1">上一页</a></span>';
			$start = 1;
		} else {
			$beforeHtml = '<span class="prev"><a href="'.$this->uri.'/page/'.($this->page - 1).'" class="a1">上一页</a></span>...';
			$start = $beforeLimit;
		}

		$endLimit = $this->page + $this->beforePageNum - $this->totalPage;
		if($endLimit >=0 ) {
			$endHtml = $this->page == $this->totalPage ? '<span class="next over">下一页</span>' : '<span class="next"><a href="'.$this->uri.'/page/'.($this->page + 1).'" class="a1">下一页</span>';
			$end = $this->totalPage;
		} else {
			$endHtml = '...<span class="next"><a href="'.$this->uri.'/page/'.($this->page + 1).'" class="a1">下一页</span>';
			$end = $this->page + $this->beforePageNum;
		}

		$html = '<div class="movie_page">'.$beforeHtml;
		for($i = $start; $i <= $end; $i++) {
			if($i == $this->page) {
				$html .= '<a href="javascript:;" class="active">'. $i .'</a>';
			} else {
				$html .= '<a href="'. $this->uri .'/page/'. $i .'" >'. $i .'</a>';
			}
		}
		$html .= $endHtml."</div>";

		return $html;
	}

}
