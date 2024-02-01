<?php

class BlogViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

    /**
     * renders <f:then> child if $condition or $or is true, otherwise renders <f:else> child.
     *
     * @param boolean $condition View helper condition
     * @param boolean $or View helper condition
     * @return string the rendered string
     */
    public function render($condition, $or) {
        if ($condition || $or) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}