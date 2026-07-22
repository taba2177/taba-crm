import { Routes } from '@angular/router';
import { Home } from './components/home/home';

// Only the Home component ships in the initial bundle (it is the landing route
// and the LCP page). Every other route is lazy-loaded so its JavaScript — and
// heavy dependencies like Swiper — stays out of the initial payload.
// (PageSpeed: reduce unused JavaScript.)
export const routes: Routes = [
  { path: '', component: Home },
  {
    path: 'contact',
    loadComponent: () => import('./components/contact/contact').then((m) => m.Contact),
  },
  {
    path: ':slug',
    loadComponent: () => import('./components/category/category').then((m) => m.Category),
  },
  {
    path: ':category/:post',
    loadComponent: () => import('./components/post-detail/post-detail').then((m) => m.PostDetail),
  },
];
