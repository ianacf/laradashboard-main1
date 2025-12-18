<?php

namespace Modules\DeviceManager\Enums\Hooks;

enum DeviceHook: string
{
    // Actions
    case DELETE_AFTER = 'device_delete_after';
    case CREATED = 'device_created';
    case UPDATED = 'device_updated';
    case ENABLE = 'device_enabled';
    case DISABLE = 'device_disabled';
    case ASSIGNED = 'device_assigned';
    case STATUS_CHANGED = 'device_status_changed';

    // Filters
    case STATUS_OPTIONS = 'device_status_options';
    case ASSIGNABLE_USERS = 'device_assignable_users';
}
