<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Vinelab\NeoEloquent\Eloquent\SoftDeletingTrait;

class MediaFile extends NeoEloquent  {

	use  SoftDeletingTrait;

    protected $label = ['File'];

    protected $fillable = ['filetype', 'filename','url'];

    public function fileable(){
        return $this->morphTo();
    }
}
