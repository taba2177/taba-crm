<?php

namespace Taba\Crm\Components\Contracts;

use Taba\Crm\Models\PostCategory;

interface SectionComponent
{
    public function key(): string;
    public function label(): array;
    public function icon(): string;
    public function description(): array;
    public function layout(): SectionLayout;
    public function sectionFields(): array;
    public function itemFields(): array;
    public function bladeView(): string;
    public function toApi(PostCategory $section): array;
    public function rules(): array;
    public function maxItems(): ?int;
}
