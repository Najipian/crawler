<?php
require 'vendor/autoload.php';
require_once("dbClient.php");

class crawler
{
    protected $url = "";
    protected $crawler = null;

    function __construct($url = 'localhost' , $linkDepth = 2){
        $this->url = $url;

        // Initiate crawler
        $this->crawler = new \Arachnid\Crawler($this->url, $linkDepth);

        $this->crawler->traverse();
    }

    public function save_links(){
        global $dbClient;


        // Get link data
        $links = $this->crawler->getLinks();

        // Get full links filtered
        $links = array_unique(array_column($links, 'absolute_url'));

        // Open database connection
        $dbClient->openDB();

        // check if the given url previously searched
        $oldUrl = $dbClient->dbconn->prepare("SELECT * FROM urls WHERE url = ? ");
        $oldUrl->bind_param('s', $this->url);
        $oldUrl->execute();

        $result = $oldUrl->get_result();
        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);


        if (!empty($data)) {
            // if its searched before delete old links

            $deleteUrlLinks = $dbClient->dbconn->prepare("DELETE FROM url_links WHERE url_id = ? ");
            $deleteUrlLinks->bind_param('i', intval($data['id']));

            $deleteUrlLinks->execute();

            // save the old url id
            $urlID = $data['id'];
        } else {
            // if it's a new search save the new link
            $newUrl = $dbClient->dbconn->prepare("INSERT INTO urls (url) VALUES (?)");

            $newUrl->bind_param('s', $this->url);

            $newUrl->execute();

            // save the url id
            $urlID = $newUrl->insert_id;
        }

        foreach ($links as $k => $v) {
            $newUrlLink = $dbClient->dbconn->prepare("INSERT INTO url_links (url_id , link) VALUES (?, ?)");
            $newUrlLink->bind_param('is', $urlID, $v);

            $newUrlLink->execute();
        }

        echo "saved";
    }
}