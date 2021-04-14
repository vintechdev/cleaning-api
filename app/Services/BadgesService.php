<?php
namespace App\Services;

use App\Badges;
use Illuminate\Support\Arr;

class BadgesService
{
    public function getModel()
    {
        return new Badges;
    }

    public function getAll($filters = [])
    {
        $query = Badges::query();

        if (Arr::has($filters,'label')) {
            $query->where('badge_label', 'like', '%'. Arr::get($filters,'label') . '%');
        }

        if (Arr::has($filters,'description')) {
            $query->where('badge_description', 'like', '%'. Arr::get($filters,'description') . '%');
        }

        return $query->orderBy('badge_label')->get();
    }

    public function findById($id)
    {
        return $this->getModel()->newQuery()
            ->where('id', $id)->first();
    }

    public function create($data)
    {
        return $this->save($this->getModel(), $data);
    }

    public function edit(int $id)
    {
        return $this->findById($id);
    }

    public function update(int $id, $data)
    {
        $badge = $this->findById($id);

        return $this->save($badge, $data);
    }

    public function save(Badges $badge, $data)
    {
        $badge->badge_label = Arr::get($data, 'badge_label');
        $badge->badge_description = Arr::get($data, 'badge_description');

        if (Arr::has($data, 'badge_icon')) {
            $badge->badge_icon = Arr::get($data, 'badge_icon');
        }

        $badge->save();

        return $badge;
    }

    public function delete($id)
    {
       /** @var Badges $badge **/
       $badge = $this->findById($id);
       $userBadgeCount = 0;

       if ($badge) {
           $userBadgeCount = \App\UserBadge::query()
               ->where('badge_id', $badge->id)
               ->whereNull('deleted_at')
               ->count();
       }

       if ($userBadgeCount > 0) {
           throw new \Exception('Can not delete badge as it is already in use!');
       }

       return $badge->delete();
    }

}