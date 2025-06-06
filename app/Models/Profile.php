<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    protected $table = 'profile';
    public $timestamps = true;
    protected $primaryKey = 'user_id';
    protected $keyType = 'int';
    protected $fillable = [
       'loan_id', 'user_id', 'full_name', 'pan_number', 'mobile_no', 'email_id', 'marital_status', 'dob', 'residence_address', 'city', 'state', 'pincode'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cityRelation()
    {
        return $this->belongsTo(Cities::class, 'city');
    }

    public function stateRelation()
    {
        return $this->belongsTo(States::class, 'state');
    }
}