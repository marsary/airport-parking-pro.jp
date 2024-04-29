<?php
namespace App\Services;

use App\Models\Member;
use App\Models\TagMember;

class LabelTagManager
{
    public static function attachTagDataToMember(Member $member = null)
    {
        if(!$member) {
            return;
        }
        $tagMembers = TagMember::with('tag', 'label')
            ->where('member_id', $member->id)
            ->where('office_id', config('const.commons.office_id'))
            ->get();

        $member->tagMembers = $tagMembers;
    }
}
