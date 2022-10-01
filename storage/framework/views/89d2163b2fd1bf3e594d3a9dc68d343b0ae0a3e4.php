<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.layout','data' => []] + (isset($attributes) ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <main>
        <h1 class="<?php echo e($group); ?>"><?php echo e($title); ?></h1>
        <div class="card <?php echo e($group); ?>">
            <img src="/images/teams/<?php echo e($curr['current_team']); ?>.png" alt="All blacks logo" class="logo" />
            <div class="name">
                <em>#<span id='number'><?php echo e($curr['number']); ?></span></em>
                <h2><span id='first-name'><?php echo e($curr['first_name']); ?></span><strong><span id='last-name'><?php echo e($curr['last_name']); ?></span></strong></h2>
            </div>
            <div class="profile">
                <img id='image' src="/images/players/<?php echo e($curr['img_dir']); ?>/<?php echo e($curr['image']); ?>" alt="<?php echo e($curr['first_name']); ?> <?php echo e($curr['last_name']); ?>" class="headshot" />
                <div class="features">
                    <?php $__currentLoopData = $curr['featured']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statistic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="feature">
                            <h3><?php echo e($statistic['label']); ?></h3>
                            <span id='<?php echo e(Str::lower($statistic['label'])); ?>'><?php echo e($statistic['value']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="bio">
                <div class="data">
                    <strong>Position</strong>
                    <span id='position'><?php echo e($curr['position']); ?></span>
                </div>
                <div class="data">
                    <strong>Weight</strong>
                    <span id='weight'><?php echo e($curr['weight']); ?></span>KG
                </div>
                <div class="data">
                    <strong>Height</strong>
                    <span id='height'><?php echo e($curr['height']); ?></span>
                </div>
                <div class="data">
                    <strong>Age</strong>
                    <span id='age'><?php echo e($curr['age']); ?></span> years
                </div>
            </div>
        </div>
        <div class="side-menu">
            <div class="menu prev <?php echo e($group); ?>">
                <a onclick="loadPlayer(<?php echo e($prev['id']); ?>, '<?php echo e($group); ?>')" id='prev-name'><?php echo e($prev['name']); ?></a>
            </div>
            <div class="menu current">
                <a id='curr-name'><?php echo e($curr['name']); ?></a>
            </div>
            <div class="menu next <?php echo e($group); ?>">
                <a onclick="loadPlayer(<?php echo e($next['id']); ?>, '<?php echo e($group); ?>')" id='next-name'><?php echo e($next['name']); ?></a>
            </div>
        </div>
    </main>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<script>
    function loadPlayer(id, group){
        event.preventDefault();
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

        $.ajaxSetup({
            headers: {
                'X-CSF-TOKEN' : CSRF_TOKEN
            }
        });

        $.ajax({
            url: "/retrieve",
            type: "get",
            data: {
                id : id,
                group: group
            },
            success: function(response) {
                $('#number').html(response['curr']['number']);
                $('#first-name').html(response['curr']['first_name']);
                $('#last-name').html(response['curr']['last_name']);
                $('#points').html(response['curr']['points']);
                $('#games').html(response['curr']['games']);
                $('#tries').html(response['curr']['tries']);
                $('#position').html(response['curr']['position']);
                $('#weight').html(response['curr']['weight']);
                $('#height').html(response['curr']['height']);
                $('#age').html(response['curr']['age']);
                $('#image').attr('src', '/images/players/' + response['curr']['img_dir'] + '/' + response['curr']['image']);
                $('#image').attr('alt', "promise");

                $('#prev-name').html(response['prev']['name']);
                $('#prev-name').attr('onclick', 'loadPlayer(' + response['prev']['id'] + ', "' + response['endpoint'] + '")');
                $('#curr-name').html(response['curr']['name']);
                $('#next-name').html(response['next']['name']);
                $('#next-name').attr('onclick', 'loadPlayer(' + response['next']['id'] + ', "' + response['endpoint'] + '")');
            }
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script><?php /**PATH C:\xampp\htdocs\project1\resources\views/player.blade.php ENDPATH**/ ?>