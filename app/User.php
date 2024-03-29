<?php

namespace App;

use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','stud_id','roll_no','expired_date','img_dir','major','year','noti_counts'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function books(){
        return $this->belongsToMany('App\Book','books_users','user_id','book_id')
            ->withPivot('issue_date','return_date');
    }

    public function reservation(){
         return $this->hasMany('App\Reservation');
    }

    public function checkExpiredDate(){
        if (Auth::check() &&  Auth::user()->expired_date <= date('Y-m-d') && Auth::user()->expired_date != null){
            $noticount = Auth::user()->noti_count;
            $noticount ++;
            Auth::user()->update(['noti_count' => $noticount]);
            return true;
        }
        return false;
    }

    public function isAdmin(){
        return Auth::check() && Auth::user()->email == 'admin@mtu.com';
    }

}
