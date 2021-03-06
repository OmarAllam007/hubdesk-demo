<?php

namespace App;

use App\Behaviors\HasCriteria;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Sla
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $due_days
 * @property integer $due_hours
 * @property integer $due_minutes
 * @property integer $response_days
 * @property integer $response_hours
 * @property integer $response_minutes
 * @property boolean $critical
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Criteria[] $criteria
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereDueDays($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereDueHours($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereDueMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereResponseDays($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereResponseHours($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereResponseMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sla whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sla extends KModel
{
    use HasCriteria;

    protected $fillable = [
        'name', 'description', 'due_days', 'due_hours', 'due_minutes',
        'response_days', 'response_hours', 'response_minutes',
        'approval_days', 'approval_hours', 'approval_minutes',
        'critical'
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $criteriaType = 'sla';

    function escalations()
    {
        return $this->hasMany(EscalationLevel::class, 'sla_id');
    }

    function getDueTime()
    {
        return ($this->due_hours * 60) + ($this->due_days * 8 * 60) + ($this->due_minutes);
    }


    function level($level)
    {
        return $this->escalations()->where('level', $level)->first();
    }

    function getMinutesAttribute()
    {
        $startTime = Carbon::parse(config('worktime.start'));
        $endTime = Carbon::parse(config('worktime.end'));

        $minutesPerDay = $endTime->diffInMinutes($startTime);

        return ($this->due_days * $minutesPerDay) + ($this->due_hours * 60) + $this->due_minutes;
    }

    public function scopeQuickSearch(Builder $query)
    {
        if (\Request::has('search')) {
            $query->where(function (Builder $q) {
                $term = '%' . \Request::get('search') . '%';
                $q->where('name', 'LIKE', $term);
            });
        }

        return $query;
    }
}
