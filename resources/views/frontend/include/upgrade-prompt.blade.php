@auth
@php
    $showUpgrade = session('show_upgrade_prompt') || request()->boolean('upgrade');
    $upgradeReason = session('upgrade_prompt_reason')
        ?: 'Your free or plan limit is reached. Purchase a plan to keep sending documents.';
    $hasPending = session()->has(\App\Services\PendingSignatureSend::SESSION_KEY);
@endphp
<div class="modal fade cs-theme-modal" id="upgradePromptModal" tabindex="-1" role="dialog" aria-hidden="true"
     data-auto-show="{{ $showUpgrade ? '1' : '0' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cs-theme-modal__content">
            <div class="modal-header cs-theme-modal__header">
                <h4 class="modal-title">Upgrade required</h4>
                <button type="button" class="close cs-theme-modal__close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body cs-theme-modal__body" style="color:#f3f6fb;">
                <p class="mb-3" style="color:#f3f6fb;">{{ $upgradeReason }}</p>
                @if($hasPending)
                    <p class="mb-3" style="color:rgba(243,246,251,.88);">
                        Your document and email are saved. After purchase you will return to this send form —
                        no need to upload again. Then press Submit.
                    </p>
                @endif
                <p class="mb-0" style="color:#f3f6fb;">Would you like to view plans and purchase now?</p>
            </div>
            <div class="modal-footer cs-theme-modal__footer" style="display:flex;gap:.75rem;justify-content:flex-end;border:0;padding:0 1.25rem 1.25rem;">
                <button type="button" class="cs-btn cs-btn--ghost-white" data-dismiss="modal">Not now</button>
                <a href="{{ url('/pricing') }}" class="cs-btn cs-btn--primary" id="upgradePromptContinue">Yes, view plans</a>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var modal = document.getElementById('upgradePromptModal');
    if (!modal || modal.getAttribute('data-auto-show') !== '1') return;
    function openUpgradeModal() {
        if (window.jQuery && jQuery.fn && jQuery.fn.modal) {
            jQuery(modal).modal('show');
            return;
        }
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openUpgradeModal);
    } else {
        openUpgradeModal();
    }
})();
</script>
@endauth
