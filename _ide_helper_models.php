<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $delai_format
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\DepartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedAt($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $material_request_id
 * @property string $libelle
 * @property string $chemin
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $delai_format
 * @property-read \App\Models\User $material_request
 * @method static \Database\Factories\DocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereChemin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereMaterialRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $reference
 * @property int $user_id
 * @property int|null $gm_approval_id
 * @property string|null $gm_comment
 * @property string|null $gm_approval_date
 * @property int|null $hod_approval_id
 * @property string|null $hod_comment
 * @property string|null $hod_approval_date
 * @property \App\Enum\MaterialRequestStatus $status
 * @property string|null $comment
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read string $gm_approval_date_format
 * @property-read string $hod_approval_date_format
 * @property-read \App\Models\User|null $gmApproval
 * @property-read \App\Models\User|null $hodApproval
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialRequestItem> $material_request_items
 * @property-read int|null $material_request_items_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MaterialRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereGmApprovalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereGmApprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereGmComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereHodApprovalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereHodApprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereHodComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereUserId($value)
 */
	class MaterialRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $material_request_id
 * @property string $designation
 * @property int $quantity
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $delai_format
 * @property-read \App\Models\MaterialRequest $material_request
 * @method static \Database\Factories\MaterialRequestItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereMaterialRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereUpdatedAt($value)
 */
	class MaterialRequestItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $department_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \App\Enum\RoleEnum $role
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department $department
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialRequest> $gm_approvals
 * @property-read int|null $gm_approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialRequest> $hod_approvals
 * @property-read int|null $hod_approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialRequest> $material_requests
 * @property-read int|null $material_requests_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

