
<!--begin: Head -->
<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(/assets/media/misc/bg-1.jpg)">
	<div class="kt-user-card__avatar">
		<img class="kt-hidden" alt="Pic" src="/assets/media/users/300_25.jpg" />

		<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
		<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">S</span>
	</div>
	<div class="kt-user-card__name">
		{{ Auth::user()->name }}
	</div>
	<div class="kt-user-card__badge">
		<span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>
	</div>
</div>

<!--end: Head -->

<!--begin: Navigation -->
<div class="kt-notification">
	
	<div class="kt-notification__custom kt-space-between">
		<a href="/auth/logout" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
	</div>
</div>

<!--end: Navigation -->