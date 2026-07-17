import { Routes } from '@angular/router';
import { DashboardPage } from './pages/dashboard/dashboard.page';
import { ExercisesPage } from './pages/exercises/exercises.page';
import { ExerciseEditorPage } from './pages/exercise-editor/exercise-editor.page';
import { ImportsPage } from './pages/imports/imports.page';

export const routes: Routes = [
  { path: '', component: DashboardPage },
  { path: 'exercises', component: ExercisesPage },
  { path: 'exercises/new', component: ExerciseEditorPage },
  { path: 'exercises/:id', component: ExerciseEditorPage },
  { path: 'imports', component: ImportsPage },
  { path: '**', redirectTo: '' },
];

