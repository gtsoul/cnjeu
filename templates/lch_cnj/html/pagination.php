<?php 

    function pagination_list_render($list)
    {
        // Reverse output rendering for right-to-left display.
        /*$html = '<ul>';
        $html .= '<li>Page</li>';
        foreach($list['pages'] as $index => $page) {
                $html .= '<li>'.$page['data'].'</li>';
                if($index != count($list['pages']))
                        $html .= '<li>-</li>';
        }
        $html .= '</ul>';

        return $html;*/
        if (JRequest :: getVar ('view') == "categorie")
		{
			// composant CNJ jeux
			$baseurl = JURI::base();
			
			$html = '<ul>';
			foreach ($list['pages'] as $page)
			{
					$html .= '<li>' . $page['data'] . '</li>';
			}$html .= '<li class="pagination-start">' . str_replace('>Début<', '>'.JHtml::_('image',$baseurl . '/templates/lch_cnj/images/btn-pagination-debut.png', '', array()).'<', utf8_decode($list['start']['data'])) . '</li>';
			$html .= '<li class="pagination-prev">' . str_replace('>Précédent<', '>'.JHtml::_('image',$baseurl . '/templates/lch_cnj/images/btn-pagination-prev.png', '', array()).'<', utf8_decode($list['previous']['data'])) . '</li>';
			$html .= '<li class="pagination-next">' . str_replace('>Suivant<', '>'.JHtml::_('image',$baseurl . '/templates/lch_cnj/images/btn-pagination-next.png', '', array()).'<', utf8_decode($list['next']['data'])) . '</li>';
			$html .= '<li class="pagination-end">' . str_replace('>Fin<', '>'.JHtml::_('image',$baseurl . '/templates/lch_cnj/images/btn-pagination-fin.png', '', array()).'<', utf8_decode($list['end']['data'])) . '</li>';
			$html .= '</ul>';
	
			return $html;
		}
		else
		{
			$html = '<ul class="pagination-actu">';
			$html .= '<li>Pages :</li>';
			foreach ($list['pages'] as $page)
			{
				$html .= '<li class="num-page">' . $page['data'] . '</li>';
			}
			$html .= '</ul>';
			return $html;
		}
    }

?>