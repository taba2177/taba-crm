import { Routes } from '@angular/router';
import { Home } from './components/home/home';
import { Contact } from './components/contact/contact';
import { Category } from './components/category/category';
import { PostDetail } from './components/post-detail/post-detail';

export const routes: Routes = [
    { path: '', component: Home },
    { path: 'contact', component: Contact },
    { path: ':slug', component: Category },
    { path: ':category/:post', component: PostDetail },
];
