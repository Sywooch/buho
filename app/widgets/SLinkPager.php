<?php
namespace app\widgets;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class SLinkPager extends LinkPager{	
	

	
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false, false, true);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false, false, true);
        }
        

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        
        if($beginPage!=0)
			$buttons[] = $this->renderPageButton('...', null, 'b-pagination__item' , false, false,true);
        
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        if($endPage!=$pageCount-1)
			$buttons[] = $this->renderPageButton('...', null, 'b-pagination__item', false, false,true);

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false, false, true);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false, false, true);
        }

        return Html::tag('div', implode("\n", $buttons), $this->options);
    }
    
    protected function renderPageButton($label, $page, $class, $disabled, $active, $span = false,$pn = false)
    {
        $options = ['class' => ($class === '' ? null : $class)];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
            return Html::tag('span', $label,$options);
        }
        
        if ($disabled) {

            return '';
        }
        if ($span) {
            return Html::tag('span', $label,$options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        if($pn){
            $linkOptions['class'] .= " {$class}";
        }
        return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
    }
}