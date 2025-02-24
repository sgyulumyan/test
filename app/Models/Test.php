<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\TestsStatusEnum;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'comment',
        'status
    '];
    // Test::withoutGlobalScopes()->find($id);
    protected $casts = [
        'status' => TestsStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('allowed', function (Builder $builder) {
            $builder->where('status', TestsStatusEnum::Allowed);
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->firstOrFail();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            if ($status === 'allowed') {
                $query->where('status', "Allowed");
            } elseif ($status === 'prohibited') {
                $query->where('status', "Prohibited");
            }
        });
    }
}



