import { Routes } from '@angular/router';
import { HomePage } from './pages/home/home.page';
import { ExercisesPage } from './pages/exercises/exercises.page';
import { ExerciseDetailPage } from './pages/exercise-detail/exercise-detail.page';
import { FavoritesPage } from './pages/favorites/favorites.page';
import { WorkoutsPage } from './pages/workouts/workouts.page';
import { LoginPage } from './pages/login/login.page';

export const routes: Routes = [
  { path: '', component: HomePage },
  { path: 'exercises', component: ExercisesPage },
  { path: 'exercises/:id', component: ExerciseDetailPage },
  { path: 'favorites', component: FavoritesPage },
  { path: 'workouts', component: WorkoutsPage },
  { path: 'login', component: LoginPage },
  { path: '**', redirectTo: '' },
];

