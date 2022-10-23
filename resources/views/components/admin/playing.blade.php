<div class="columns">
    <div class="column">
        <p><strong>Theme Category</strong></p>
        <p class="select">
            <select wire:model="themeCategorySelected">
                @foreach($themeCategories as $themeCategory)
                    <option value="{{ $themeCategory->id }}">{{ $themeCategory->name }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <button class="button" type="button" wire:click="createThemeCategory">Create theme category</button>
        </p>
    </div>
    <div class="column">
        <p><strong>Theme</strong></p>
        <p class="select">
            <select wire:model="themeSelected">
                @foreach($themes as $theme)
                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <button class="button" type="button" wire:click="createTheme">Create theme</button>
        </p>
    </div>
    <div class="column">
        <p><strong>Month</strong></p>
        <button class="button" type="button" wire:click="createMonth">Create next month</button>
    </div>
</div>
