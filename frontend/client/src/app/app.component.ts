import { Component } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterLink, RouterOutlet],
  template: `
    <div class="shell">
      <header class="topbar">
        <a routerLink="/" class="brand">Gym App</a>
        <nav class="nav">
          <a routerLink="/exercises">Exercises</a>
          <a routerLink="/favorites">Favorites</a>
          <a routerLink="/workouts">Workouts</a>
          <a routerLink="/login">Login</a>
        </nav>
      </header>
      <main class="content">
        <router-outlet></router-outlet>
      </main>
    </div>
  `,
  styles: [`
    :host { display: block; font-family: Inter, system-ui, sans-serif; color: #111827; }
    .shell { min-height: 100vh; background: linear-gradient(180deg, #fff, #f8fafc); }
    .topbar { display: flex; justify-content: space-between; gap: 16px; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: rgba(255,255,255,.92); backdrop-filter: blur(10px); }
    .brand { font-weight: 800; text-decoration: none; color: #111827; }
    .nav { display: flex; gap: 12px; flex-wrap: wrap; }
    .nav a { text-decoration: none; color: #4b5563; }
    .nav a:hover { color: #111827; }
    .content { padding: 24px; max-width: 1100px; margin: 0 auto; }
  `],
})
export class AppComponent {}

