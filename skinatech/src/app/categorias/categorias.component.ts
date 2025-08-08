import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CategoriasService } from './categorias.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-categorias',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './categorias.component.html',
})
export class CategoriasComponent implements OnInit {
  categorias: any[] = [];
  nuevaCategoria = { nombre: '' };
  editandoId: number | null = null;
  categoriaEditada = { nombre: '' };

  // Propiedades para controlar los modales
  mostrarModalCrear = false;
  mostrarModalEditar = false;

  rol: string | null = null;

  constructor(
    private categoriaService: CategoriasService,
    private toastr: ToastrService
  ) {}

  ngOnInit() {
    this.rol = localStorage.getItem('rol');
    this.obtenerCategorias();
  }

  obtenerCategorias() {
    this.categoriaService.getCategorias().subscribe({
      next: data => this.categorias = data,
      error: () => this.toastr.error('Error al cargar categorías')
    });
  }

  crearCategoria() {
    this.categoriaService.crearCategoria(this.nuevaCategoria).subscribe({
      next: () => {
        this.toastr.success('Categoría creada correctamente');
        this.nuevaCategoria = { nombre: '' };
        this.obtenerCategorias();
      },
      error: () => this.toastr.error('Error al crear categoría')
    });
  }

  editarCategoria(categoria: any) {
    this.editandoId = categoria.id;
    this.categoriaEditada = { nombre: categoria.nombre };
  }

  guardarEdicion(id: number) {
    this.categoriaService.actualizarCategoria(id, this.categoriaEditada).subscribe({
      next: () => {
        this.toastr.success('Categoría actualizada correctamente');
        this.editandoId = null;
        this.obtenerCategorias();
      },
      error: () => this.toastr.error('Error al actualizar categoría')
    });
  }

  cancelarEdicion() {
    this.editandoId = null;
  }

  eliminarCategoria(id: number) {
    this.categoriaService.eliminarCategoria(id).subscribe({
      next: () => {
        this.toastr.success('Categoría eliminada correctamente');
        this.obtenerCategorias();
      },
      error: () => this.toastr.error('Error al eliminar categoría')
    });
  }

  activarCategoria(id: number) {
    this.categoriaService.actualizarCategoria(id, { estado: 'activa' }).subscribe({
      next: () => {
        this.toastr.success('Categoría activada correctamente');
        this.obtenerCategorias();
      },
      error: () => this.toastr.error('Error al activar categoría')
    });
  }

  abrirModalCrear() {
    this.mostrarModalCrear = true;
  }
  cerrarModalCrear() {
    this.mostrarModalCrear = false;
  }

  abrirModalEditar(categoria: any) {
    this.editandoId = categoria.id;
    this.categoriaEditada = { nombre: categoria.nombre };
    this.mostrarModalEditar = true;
  }
  cerrarModalEditar() {
    this.editandoId = null;
    this.mostrarModalEditar = false;
  }
}
