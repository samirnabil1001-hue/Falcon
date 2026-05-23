<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Auth\Access\Response;

class PotentialCustomerPolicy
{
    /**
     * التحقق من صلاحية العرض الموحد للعميل
     */
    public function view(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO() || $user->id === $potentialCustomer->user_id;
    }

    /**
     * التحقق من صلاحية التحديث العام (الاسم، الهاتف، المصدر)
     */
    public function update(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO() || $user->id === $potentialCustomer->user_id;
    }

    /**
     * التحقق من صلاحية تحديث الحالة فقط
     */
    public function updateStatus(User $user, PotentialCustomer $potentialCustomer): Response
    {
        // الحصول على القيمة النصية للحالة الحالية للعميل
        $currentStatus = is_object($potentialCustomer->status)
            ? $potentialCustomer->status->value
            : $potentialCustomer->status;

        // 1. شرط الحظر: إذا كانت الحالة مغلقة (مؤكدة أو ملغية) يمنع التعديل نهائياً لأي رتبة
        $lockedStatuses = [
            PotentialCustomerStatus::CONFIRMED->value,
            PotentialCustomerStatus::CANCELLED->value
        ];

        if (in_array($currentStatus, $lockedStatuses)) {
            return Response::deny('عذراً، لا يمكن تعديل حالة العميل بعد اعتماده أو إلغائه.');
        }

        // 2. شرط الرتبة: الـ CEO يمكنه التعديل دائماً، باقي الرتب يجب أن يكونوا هم من أضافوا العميل
        if ($user->isCEO()) {
            return Response::allow();
        }

        if ($user->id === $potentialCustomer->user_id) {
            return Response::allow();
        }

        return Response::deny('ليس لديك الصلاحية لتعديل حالة هذا العميل.');
    }

    /**
     * التحقق من صلاحية الحذف
     */
    public function delete(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO() || $user->id === $potentialCustomer->user_id;
    }

    /**
     * التحقق من صلاحية نقل تبعية العميل لموظف آخر (الـ CEO فقط)
     */
    public function updateAddedBy(User $user, PotentialCustomer $potentialCustomer): bool
    {
        // تم استخدام ميثود الموديل الموحدة لمشروعك لضمان ثبات الكود
        return $user->isCEO();
    }
}