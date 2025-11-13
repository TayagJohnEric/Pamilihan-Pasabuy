# GCash Reminder Feature Implementation

## Overview
Implemented a persistent modal reminder system that notifies riders to complete their GCash information (gcash_number, gcash_qr_path, gcash_name) until all fields are filled in. The modal appears on all rider pages and is only visible to riders who have incomplete GCash information. When dismissed, it automatically reappears after 30 seconds to ensure riders don't forget.

## Files Modified/Created

### 1. **Rider Model** (`app/Models/Rider.php`)
- Added `hasIncompleteGCashInfo()` method
- Returns `true` if any of the three GCash fields are empty
- Used to determine when to show the reminder

### 2. **GCash Reminder Component** (`resources/views/components/rider/gcash-reminder.blade.php`)
- New Blade component that displays a modal reminder
- Features:
  - **Modal Design**: Centered overlay with backdrop blur
  - **Warning Icon**: Yellow alert icon in modal header
  - **Dynamic List**: Shows which specific GCash fields are missing
  - **Two Action Buttons**:
    - "Complete Now" - Links to profile edit page
    - "Remind Me Later" - Dismisses modal temporarily
  - **Auto-Reappear**: Modal reappears after 30 seconds when dismissed
  - **Smart Timing**: Tracks dismissal time across page navigations
  - **Backdrop Click**: Can be dismissed by clicking outside modal
  - **Fully Responsive**: Adapts to mobile, tablet, and desktop
  - **Accessibility**: Proper ARIA labels and roles

### 3. **Rider Layout** (`resources/views/layout/rider.blade.php`)
- Integrated the GCash reminder modal component
- Placed outside main content area (fixed overlay)
- Appears on all rider pages automatically

## How It Works

### Display Logic
```php
@if(Auth::check() && Auth::user()->role === 'rider' && Auth::user()->rider && Auth::user()->rider->hasIncompleteGCashInfo())
    // Show reminder modal
@endif
```

The modal is displayed when:
1. User is authenticated
2. User has 'rider' role
3. User has a rider profile
4. At least one GCash field is empty

### Missing Fields Detection
The modal dynamically shows which fields are missing:
- GCash Mobile Number (`gcash_number`)
- GCash Account Name (`gcash_name`)
- GCash QR Code (`gcash_qr_path`)

### User Experience Flow

#### Initial Display
1. Rider logs in or navigates to any page
2. Modal appears immediately if GCash info is incomplete
3. Modal centers on screen with backdrop overlay

#### Dismissal Options
1. **"Complete Now"** button → Redirects to profile edit page
2. **"Remind Me Later"** button → Dismisses modal for 30 seconds
3. **Click backdrop** → Same as "Remind Me Later"

#### Auto-Reappear Logic
1. When dismissed, timestamp is stored in `sessionStorage`
2. After 30 seconds, modal automatically reappears
3. If rider navigates to another page within 30 seconds:
   - Remaining time is calculated
   - Modal appears after the remaining time
4. After 30 seconds total, modal shows again

#### Example Timeline
```
0:00 - Modal appears
0:05 - Rider clicks "Remind Me Later"
0:35 - Modal reappears (30 seconds later)
0:40 - Rider navigates to another page
0:40 - Modal calculates 25 seconds remaining
1:05 - Modal reappears (25 seconds after navigation)
```

## Profile Edit Integration

The existing rider profile edit page (`resources/views/rider/profile/edit.blade.php`) already includes:
- GCash Number input field
- GCash Account Name input field
- GCash QR Code upload field with preview

Route: `{{ route('rider.profile.edit') }}`

## Technical Details

### Session Storage & Timing
- **Dismissal Tracking**: Stores timestamp in `sessionStorage.setItem('gcash-reminder-dismissed-at', Date.now())`
- **30-Second Timer**: Uses `setTimeout()` to schedule modal reappearance
- **Smart Calculation**: On page load, calculates remaining time from dismissal
- **Cross-Page Persistence**: Dismissal time persists across page navigations within the same session
- **Session Scope**: Clears when browser tab/window is closed

### JavaScript Functions

#### `showGCashModal()`
- Removes `hidden` class from modal
- Prevents background scrolling (`overflow: hidden`)

#### `dismissGCashModal()`
- Hides modal and restores scrolling
- Stores dismissal timestamp
- Sets 30-second timeout for reappearance
- Clears any existing timeouts

#### `DOMContentLoaded` Handler
- Checks for existing dismissal timestamp
- Calculates time since dismissal
- If < 30 seconds: schedules modal for remaining time
- If ≥ 30 seconds: shows modal immediately
- If no timestamp: shows modal immediately

### Modal Design
- **Fixed Overlay**: `z-50` ensures it appears above all content
- **Backdrop**: Semi-transparent gray overlay (`bg-opacity-75`)
- **Centered**: Uses flexbox for perfect centering
- **Responsive Width**: `max-w-lg` on desktop, full width on mobile
- **Smooth Transitions**: Fade-in/out effects
- **Accessibility**: Proper ARIA attributes (`role="dialog"`, `aria-modal="true"`)

### Styling
- **Yellow Theme**: Warning color scheme (`bg-yellow-50`, `text-yellow-600`)
- **Icon**: Alert triangle icon in circular badge
- **Buttons**: 
  - Primary: Gradient emerald-to-teal
  - Secondary: White with gray border
- **Shadow**: `shadow-xl` for depth
- **Rounded Corners**: `rounded-lg` for modern look

## Testing Checklist

### Functional Testing
- [ ] Modal appears immediately for riders with incomplete GCash info
- [ ] Modal does NOT appear for riders with complete GCash info
- [ ] Modal does NOT appear for non-rider users
- [ ] Clicking "Complete Now" navigates to profile edit page
- [ ] Clicking "Remind Me Later" dismisses modal
- [ ] Clicking backdrop dismisses modal
- [ ] Modal reappears after exactly 30 seconds
- [ ] After completing all GCash fields, modal disappears permanently
- [ ] Dismissal time persists across page navigations
- [ ] Remaining time is calculated correctly on page load

### Visual Testing
- [ ] Modal centers correctly on all screen sizes
- [ ] Backdrop overlay is visible and semi-transparent
- [ ] Modal displays correctly on mobile screens
- [ ] Modal displays correctly on tablet screens
- [ ] Modal displays correctly on desktop screens
- [ ] All missing fields are listed with icons
- [ ] Buttons are properly styled and aligned
- [ ] Text is readable and not truncated
- [ ] Modal has proper shadow and rounded corners

### Timing Testing
- [ ] Dismiss modal, wait 30 seconds → modal reappears
- [ ] Dismiss modal, navigate to another page within 30s → modal appears after remaining time
- [ ] Dismiss modal, close tab, reopen → modal appears immediately (new session)
- [ ] Dismiss modal, refresh page within 30s → modal appears after remaining time
- [ ] Keep modal open, navigate to another page → modal appears immediately

### Integration Testing
- [ ] Modal appears on dashboard
- [ ] Modal appears on orders page
- [ ] Modal appears on earnings page
- [ ] Modal appears on profile page
- [ ] Modal appears on all other rider pages
- [ ] Profile edit form saves GCash information correctly
- [ ] After saving complete GCash info, modal never appears again
- [ ] Background scrolling is prevented when modal is open
- [ ] Background scrolling is restored when modal is dismissed

## Future Enhancements (Optional)

1. **Adjustable Timer**: Make the 30-second interval configurable via settings
2. **Progressive Delays**: Increase delay time after multiple dismissals (30s → 1m → 5m)
3. **Database-backed dismissal**: Store dismissal count in database for analytics
4. **Email notifications**: Send email reminders for incomplete GCash info
5. **Admin notifications**: Alert admins about riders with incomplete payout info
6. **Deadline warnings**: Add urgency if payout is pending but GCash info is incomplete
7. **Snooze Options**: Allow riders to choose snooze duration (30s, 1h, 1 day)
8. **Progress Indicator**: Show which fields are completed vs. missing

## Related Files

- Model: `app/Models/Rider.php`
- Migration: `database/migrations/2025_06_14_012124_create_riders_table.php`
- Component: `resources/views/components/rider/gcash-reminder.blade.php`
- Layout: `resources/views/layout/rider.blade.php`
- Profile Edit: `resources/views/rider/profile/edit.blade.php`
- Controller: `app/Http/Controllers/Rider/RiderProfileController.php`
- Routes: `routes/web.php` (rider.profile.edit, rider.profile.update)
