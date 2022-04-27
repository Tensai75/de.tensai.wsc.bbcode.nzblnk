<?php
namespace wcf\system\bbcode;
use wcf\system\WCF;

/**
 * Parses the [nzblnk] bbcode tag.
 *
 * @author        Tensai
 * @copyright    2021 by Tensai
 * @license        MIT
 * @subpackage    system.bbcode
 * @category    Community Framework
 */
class nzblnkBBCode extends AbstractBBCode{

    /**
     * @see BBCode::getParsedTag()
     * @param array        $openingTag
     * @param string       $content
     * @param array        $closingTag
     * @param BBCodeParser $parser
     * @return string
     */
    public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
        if ($parser->getOutputType() == 'text/html') {
            if (is_array($openingTag['attributes']) && count($openingTag['attributes']) > 0 && $openingTag['attributes'][0] != '') {
                $re = '/(?:(?:(?:&amp;|&|\?)?t=(?<title>.*?)(?=(?:&amp;|&|\?)?(?:[htpg]=)|$))|(?:(?:&amp;|&|\?)?h=(?<header>.*?)(?=(?:&amp;|&|\?)?(?:[htpg]=)|$))|(?:(?:&amp;|&|\?)?p=(?<password>.*?)(?=(?:&amp;|&|\?)?(?:[htpg]=)|$))|(?:(?:&amp;|&|\?)?g=(?<group>.*?)(?=(?:&amp;|&|\?)?(?:[htpg]=)|$)))+/i';
                preg_match($re, html_entity_decode($openingTag['attributes'][0]), $matches, PREG_UNMATCHED_AS_NULL);
				if ($matches['header']) {
					$nzblnk = 'nzblnk://';
					$nzblnk .= $matches['header'] ? '?h='.rawurlencode(rawurldecode($matches['header'])) : '';
					$nzblnk .= $matches['title'] ? '&t='.rawurlencode(rawurldecode($matches['title'])) : '';
					$nzblnk .= $matches['password'] ? '&p='.rawurlencode(rawurldecode($matches["password"])) : '';
					$nzblnk .= $matches['group'] ? '&g='.rawurlencode(rawurldecode($matches['group'])) : '';
					WCF::getTPL()->assign([
							'nzblnk' => $nzblnk,
							'wcf_path' => (WCF::getPath()),
					]);
					return WCF::getTPL()->fetch('nzblnkBBCodeTag');
				}
            }
        }
        return '';
    }
}
