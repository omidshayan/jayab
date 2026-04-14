<?php
require_once 'Http/Controllers/branches/Branches.php';

// branches routes
uri('manage-branches', 'App\Branches', 'manageBranches');
uri('branch-store', 'App\Branches', 'branchStore', 'POST');
uri('edit-branch/{id}', 'App\Branches', 'editBranch');
uri('edit-branch-store/{id}', 'App\Branches', 'editBranchStore', 'POST');
uri('branch-details/{id}', 'App\Branches', 'branchDetails');
uri('change-status-branch/{id}', 'App\Branches', 'changeStatusBranch');
