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
                $re = '/(?:[&?](?:amp;)?([htpgd])=([^&]+))/i';
                preg_match_all($re, html_entity_decode($openingTag['attributes'][0]), $matches, PREG_SET_ORDER);
                foreach (str_split('htpgd') as $parameter) {
                    $parameters[$parameter] = [];
                }
                foreach ($matches as $values) {
                    array_push($parameters[$values[1]],$values[2]);
                }
                if (count($parameters['h']) > 0) {
                    $nzblnk = 'nzblnk://?h='.rawurlencode(rawurldecode($parameters['h'][0]));
                    $nzblnk .= count($parameters['t']) > 0 ? '&t='.rawurlencode(rawurldecode($parameters['t'][0])) : '';
                    $nzblnk .= count($parameters['p']) > 0 ? '&p='.rawurlencode(rawurldecode($parameters['p'][0])) : '';
                    if (count($parameters['g']) > 0) {
                        foreach ($parameters['g'] as $group) {
                            $nzblnk .= '&g='.rawurlencode(rawurldecode($group));
                        }
                    }
                    $nzblnk .= count($parameters['d']) > 0 ? '&d='.rawurlencode(rawurldecode($parameters['d'][0])) : '';
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
