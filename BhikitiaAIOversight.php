<?php
if (!defined('MEDIAWIKI')) {
    die("This file is part of MediaWiki.");
}

class BhikitiaAIOversight {

    public static function onParserInit(Parser $parser) {
        $parser->setFunctionHook(
            'BhikitiaAIOversight',
            [ self::class, 'render' ]
        );
        return true;
    }

    public static function render(Parser $parser) {

        $titleObj = $parser->getTitle();
        $title = $titleObj->getText();

        $wikiPage = WikiPage::factory($titleObj);
        $contentObj = $wikiPage->getContent();

        if (!$contentObj) {
            return "AI Error: No content found.";
        }

        $content = ContentHandler::getContentText($contentObj);

        $response = Http::post(
            "http://127.0.0.1:3000/analyze",
            [
                'postData' => json_encode([
                    'title' => $title,
                    'content' => $content
                ]),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        if (!$response) {
            return "AI Error: Engine not reachable.";
        }

        $data = json_decode($response, true);

        if (!$data) {
            return "AI Error: Invalid response.";
        }

        return "
{| class='wikitable'
! colspan='2' | Bhikitia AI Oversight
|-
| Summary
| {$data['summary']}
|-
| Key Facts
| {$data['keyFacts']}
|-
| Confidence
| {$data['confidence']}%
|-
| Last Review
| {$data['date']}
|}
";
    }
}