import { Component, inject, signal, OnInit, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { Title, Meta } from '@angular/platform-browser';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { register } from 'swiper/element/bundle';

register();

@Component({
  selector: 'app-post-detail',
  imports: [RouterLink, ScrollRevealDirective],
  templateUrl: './post-detail.html',
  styleUrl: './post-detail.scss',
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class PostDetail implements OnInit {
  private api = inject(ApiService);
  private route = inject(ActivatedRoute);
  private titleSvc = inject(Title);
  private meta = inject(Meta);

  post = signal<any>(null);
  category = signal<any>(null);
  relatedPosts = signal<any[]>([]);
  loading = signal(true);
  lightboxImage = signal<string | null>(null);
  t = t;

  private gallerySlugs = ['our-works', 'wooden-decorations', 'glass-facades', 'aluminum-works'];
  private articleSlugs = ['about', 'services', 'partners'];

  get isGalleryLayout(): boolean {
    const slug = this.category()?.slug;
    return this.gallerySlugs.includes(slug);
  }

  get isArticleLayout(): boolean {
    const slug = this.category()?.slug;
    return this.articleSlugs.includes(slug);
  }

  ngOnInit() {
    this.route.params.subscribe(params => {
      this.loading.set(true);
      window.scrollTo({ top: 0 });
      this.api.getPost(params['category'], params['post']).subscribe({
        next: (res: any) => {
          this.post.set(res.post);
          this.category.set(res.category);
          this.relatedPosts.set(res.relatedPosts || []);
          this.loading.set(false);
          const title = t(res.post?.meta_title) || t(res.post?.title) || '';
          const desc  = t(res.post?.meta_description) || t(res.post?.description) || '';
          const image = res.post?.image?.url || '';
          this.titleSvc.setTitle(title);
          this.meta.updateTag({ name: 'description', content: desc });
          this.meta.updateTag({ property: 'og:title', content: title });
          this.meta.updateTag({ property: 'og:description', content: desc });
          this.meta.updateTag({ property: 'og:image', content: image });
          this.meta.updateTag({ name: 'twitter:card', content: 'summary_large_image' });
          this.meta.updateTag({ name: 'twitter:title', content: title });
          this.meta.updateTag({ name: 'twitter:description', content: desc });
          this.meta.updateTag({ name: 'twitter:image', content: image });
          setTimeout(() => this.initSwiper(), 500);
        },
        error: () => this.loading.set(false),
      });
    });
  }

  private initSwiper() {
    const swiperEl = document.querySelector('swiper-container#detail-swiper') as any;
    if (swiperEl && !swiperEl.swiper) {
      Object.assign(swiperEl, {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 3,
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        coverflowEffect: {
          rotate: 5,
          stretch: 0,
          depth: 150,
          modifier: 1.5,
          slideShadows: true,
        },
        pagination: { clickable: true },
        breakpoints: {
          320: { slidesPerView: 1.2 },
          640: { slidesPerView: 1.8 },
          1024: { slidesPerView: 2.5 },
        },
      });
      swiperEl.initialize();
    }
  }

  getImage(item: any): string {
    return item?.image?.url || item?.images?.[0]?.url || '/assets/images/default.jpg';
  }

  openLightbox(url: string) {
    this.lightboxImage.set(url);
  }

  closeLightbox() {
    this.lightboxImage.set(null);
  }
}
