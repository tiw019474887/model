<?php

class ProjectMobileApiController extends BaseController
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
        $researchProject = ResearchProject::with(['faculty','cover'])->get();

        return $this->getResponse(true, $researchProject);
    }

    public function getView($id){
        $researchProject = ResearchProject::with(['photos','faculty','cover'])->find((int)$id);
        return $this->getResponse(true, $researchProject);
    }

    public function getCover($id){
        $researchProject = ResearchProject::with([])->find((int)$id);
        $cover = $researchProject->cover()->first();
        return $this->getResponse(true,$cover);
    }



}
