import { Component, inject, signal, OnInit } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';

@Component({
  selector: 'app-category',
  imports: [RouterLink],
  templateUrl: './category.html',
  styleUrl: './category.scss',
})
export class Category implements OnInit {
  private api = inject(ApiService);
  private route = inject(ActivatedRoute);

  category = signal<any>(null);
  posts = signal<any[]>([]);
  loading = signal(true);
  t = t;

  ngOnInit() {
    this.route.params.subscribe(params => {
      this.loading.set(true);
      this.api.getCategory(params['slug']).subscribe({
        next: (res: any) => {
          this.category.set(res.category);
          this.posts.set(res.posts || []);
          this.loading.set(false);
          document.title = t(res.category?.name, 'Category');
        },
        error: () => this.loading.set(false),
      });
    });
  }

  getImage(post: any): string {
    return post.image?.url || post.images?.[0]?.url || '/assets/images/default.jpg';
  }
}
