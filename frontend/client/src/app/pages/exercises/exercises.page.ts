import { Component } from '@angular/core';
import { NgFor } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  standalone: true,
  imports: [RouterLink, NgFor],
  template: `
    <section>
      <h2>Exercises</h2>
      <p>Search, filter and browse the catalog from the API.</p>
      <div class="grid">
        <article *ngFor="let item of items">
          <a [routerLink]="['/exercises', item.id]">{{ item.name }}</a>
          <p>{{ item.bodyPart }} • {{ item.equipment }}</p>
        </article>
      </div>
    </section>
  `,
  styles: [`
    .grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-top: 20px; }
    article { padding: 16px; border: 1px solid #e5e7eb; border-radius: 18px; background: white; }
    a { color: #111827; font-weight: 700; text-decoration: none; }
    p { color: #6b7280; margin: 8px 0 0; }
  `],
})
export class ExercisesPage {
  items = [
    { id: '0001', name: '3/4 sit-up', bodyPart: 'waist', equipment: 'body weight' },
    { id: '0002', name: 'side bend', bodyPart: 'waist', equipment: 'dumbbell' },
  ];
}
