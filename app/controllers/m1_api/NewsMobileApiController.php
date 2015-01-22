<?php

class NewsMobileApiController extends BaseController
{

    public function __construct()
    {
        $this->afterFilter(function ($response) {
            header('Access-Control-Allow-Origin: *');
            return $response;
        });
    }

    public function getIndex()
    {
        $news = News::with(['photos'])->get();
        $outputNews = [];
        foreach ($news as $n) {
              array_push($outputNews,$n);
        }

        return $this->getResponse(true, $outputNews);
    }

    public function getView($id){
        $news = News::with(['photos','user.profileImage'])->find((int)$id);
        return $this->getResponse(true, $news);
    }



}
