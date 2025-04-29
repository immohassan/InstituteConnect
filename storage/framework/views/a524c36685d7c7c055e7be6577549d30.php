<div class="w-full h-full min-h-full flex rounded-lg" >
    <div class="relative  w-full h-full  border-r  border-[var(--wc-light-border)] dark:border-[var(--wc-dark-border)]  md:w-[360px] lg:w-[400px] xl:w-[500px] shrink-0 overflow-y-auto  ">
      <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('wirechat.chats', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1037896896-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?> 
    </div>
    <main class="hidden md:grid h-full min-h-full w-full bg-[var(--wc-light-primary)] dark:bg-[var(--wc-dark-primary)]  h-full relative overflow-y-auto"  style="contain:content">

    <div class="m-auto text-center justify-center flex gap-3 flex-col   items-center  col-span-12">

             <h4 class="font-medium p-2 px-3 rounded-full font-semibold bg-[var(--wc-light-secondary)] dark:bg-[var(--wc-dark-secondary)] dark:text-white dark:font-normal"><?php echo app('translator')->get('wirechat::pages.chat.messages.welcome'); ?></h4>
          
        </div>
    </main>
</div><?php /**PATH A:\New folder\InstituteConnect\vendor\namu\wirechat\src/../resources/views/livewire/pages/chats.blade.php ENDPATH**/ ?>