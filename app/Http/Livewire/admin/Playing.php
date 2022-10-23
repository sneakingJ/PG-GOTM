<?php

namespace App\Http\Livewire\Admin;

use App\Models\Theme;
use App\Models\ThemeCategory;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 *
 */
class Playing extends Component
{
    /**
     * @var Collection
     */
    public Collection $themeCategories;

    /**
     * @var Collection
     */
    public Collection $themes;

    /**
     * @var int
     */
    public int $themeCategorySelected = 1;

    /**
     * @var int
     */
    public int $themeSelected = 1;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->themeCategories = ThemeCategory::get();

        # TODO: Show only the ones not used yet
        # TODO: Button to add 1 to used theme counter
        # TODO: Create new theme category, new theme and new month

        $this->themes = empty($this->themeCategorySelected) ? new Collection() : Theme::where('theme_category_id', $this->themeCategorySelected)->get();

        return view('components.admin.playing');
    }
}
