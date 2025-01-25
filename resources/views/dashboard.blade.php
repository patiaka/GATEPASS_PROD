<x-app-layout>
    <div class="row">
        <x-card :count="$CountUser" :title="'Total User'" :icon="'bx bx-user'" :color="'info'" />
        <x-card :count="$CountDepartment" :title="'Total Department'" :icon="'bx bx-building'" :color="'info'" />
        <x-card :count="$CountCompagnie" :title="'Total Compagny'" :icon="'bx bx-building'" :color="'info'" />
        <x-card :count="$MaterialPending" :title="'Pending Material Request'" :icon="'bx bx-file-blank'"
            :color="'info'" />
        <x-card :count="$MaterialApproved" :title="'Approved Material Request'" :icon="'bx bx-file-blank'"
            :color="'success'" />
        <x-card :count="$MaterialRejected" :title="'Rejected Material Request'" :icon="'bx bx-file-blank'"
            :color="'danger'" />
        <x-card :count="$MaterialProgress" :title="'In Progress Material Request'" :icon="'bx bx-file-blank'"
            :color="'warning'" />
        <x-card :count="$CarPending" :title="'Pending Car Request'" :icon="'bx bx-car'" :color="'info'" />
        <x-card :count="$CarApproved" :title="'Approved Car Request'" :icon="'bx bx-car'" :color="'success'" />
        <x-card :count="$CarRejected" :title="'Rejected Car Request'" :icon="'bx bx-car'" :color="'danger'" />
        <x-card :count="$CarProgress" :title="'In Progress Car Request'" :icon="'bx bx-car'" :color="'warning'" />
    </div>
</x-app-layout>
