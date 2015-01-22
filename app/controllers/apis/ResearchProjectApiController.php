<?php

class ResearchProjectApiController extends BaseController {
    

    public function getIndex() {

        $researchProjects = ResearchProject::with(['researchers','faculty'])->get();

        return $this->getResponse(true, $researchProjects);
    }

    public function getView($id){
        $researchProject = ResearchProject::with('researchers')->find((int)$id);
        return $this->getResponse(true,$researchProject);
    }

    public function getViewPhoto($id,$skip=0){
        $researchProject = ResearchProject::find((int)$id);
        $photos = $researchProject->photos()->take(8)->skip((int)$skip)->get();
        return $this->getResponse(true,$photos,null);
    }

    public function getViewCover($id){
        $researchProject = ResearchProject::find((int)$id);
        $cover = $researchProject->cover()->first();
        return $this->getResponse(true,$cover,null);
    }

    public function postSave() {


        if (Input::has('id')) {
            $researchProject = ResearchProject::findOrNew(Input::get('id'));
            $researchProject->update(Input::all());
        } else {
            $researchProject = ResearchProject::firstOrNew(Input::all());
        }
        $researchProject->save();
        if (Input::has('researchers')) {
            $researchers = Input::get('researchers');
            $r_ids = [];
            foreach ($researchers as $r) {
                array_push($r_ids, (int) $r['id']);
            }
            $researchProject->researchers()->sync($r_ids);
        } else {
            $researchProject->researchers()->sync([]);
        }

        if (Input::has('faculty')){
            $fid = Input::get('faculty')['id'];
            $faculty = Faculty::find((int)$fid);
            $researchProject->faculty()->associate($faculty)->save();
        }

        return $this->getResponse(true, $researchProject);
    }

    public function postDelete() {

        if (Input::has('id')) {
            $researchProject = ResearchProject::find(Input::get('id'));
            $researchProject->delete();

            return $this->getResponse(true, null, "Research Project [" + Input::get('id') + "] has been delete successfully.");
        } else {
            return $this->getResponse(false, null, "You must send [id] of ResearchProject to delete it");
        }
    }

    public function postUploadImage($id){
        $researchProject = ResearchProject::find((int)$id);

        if(Input::has('filename')){
            $filename = Input::get('filename');
            $filetype = Input::get('filetype');
            $base64 = Input::get('base64');

            $photo = $this->createPhoto($researchProject->id,$filename,$filetype,$base64);
            $photo->save();
            $researchProject->photos()->save($photo);

            return $this->getResponse(true,$photo,null);
        }
    }

    public function postUploadCover($id){
        if(Input::has('filename')){
            $researchProject = ResearchProject::find((int)$id);
            $filename = Input::get('filename');
            $filetype = Input::get('filetype');
            $base64 = Input::get('base64');
            $photo = $this->createPhoto($researchProject->id,$filename,$filetype,$base64);
            $photo->save();
            $researchProject->cover()->save($photo);

            return $this->getResponse(true,$photo,null);
        }
    }


    public function postUploadFile($id){
        $researchProject = ResearchProject::find((int)$id);

        if(Input::has('filename')){
            $filename = Input::get('filename');
            $filetype = Input::get('filetype');
            $base64 = Input::get('base64');

            $file = $this->createFile($researchProject->id,$filename,$filetype,$base64);
            $file->save();
            $researchProject->files()->save($file);

            return $this->getResponse(true,$file,null);
        }
    }

    public function postDeletePhoto(){
        $id = Input::get('id');
        $photo = Photo::find((int)$id);
        $photo->photoable()->dissociate();
        $photo->delete();
        return $this->getResponse(true,null,null);
    }

}
