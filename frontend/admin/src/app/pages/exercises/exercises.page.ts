import { Component } from '@angular/core';
import { NgFor } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  standalone: true,
  imports: [RouterLink, NgFor],
  template: `
    <section>
      <h2>Exercises</h2>
      <a routerLink="/exercises/new">New exercise</a>
      <ul>
        <li *ngFor="let item of items">
          <a [routerLink]="['/exercises', item.id]">{{ item.name }}</a>
        </li>
      </ul>
    </section>
  `,
})
export class ExercisesPage {
  items = [
    { id: '0001', name: '3/4 sit-up' },
    { id: '0002', name: 'side bend' },
  ];
}
