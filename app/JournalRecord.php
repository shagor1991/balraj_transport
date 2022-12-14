<?php

namespace App;
use App\Models\AccountHead;
use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Model;

class JournalRecord extends Model
{
    public function journal(){
        return $this->belongsTo(Journal::class);
    }

    public function ac_head(){
        return $this->belongsTo(Models\AccountHead::class,'account_head_id');
    }

    public function master_ac(){
        return $this->belongsTo(MasterAccount::class,'master_account_id');
    }
}
