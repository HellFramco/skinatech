import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SubcategoriasService } from './subcategorias.service';
import { CategoriasService } from '../categorias/categorias.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-subcategorias',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './subcategorias.component.html',
})
export class SubcategoriasComponent implements OnInit {
  subcategorias: any[] = [];
  categorias: any[] = [];
  rol: string | null = null;

  // Crear
  nuevaSubcategoria = { nombre: '', categoria_id: null };

  // Editar
  editandoId: number | null = null;
  subcategoriaEditada = { nombre: '', categoria_id: null };

  // Modales
  mostrarModalCrear = false;
  mostrarModalEditar = false;

  constructor(
    private subcatService: SubcategoriasService,
    private catService: CategoriasService,
    private toastr: ToastrService
  ) {}

  ngOnInit() {
    this.rol = localStorage.getItem('rol');
    this.obtenerSubcategorias();
    this.obtenerCategorias();
  }

  obtenerSubcategorias() {
    this.subcatService.getSubcategorias().subscribe({
      next: data => this.subcategorias = data,
      error: () => this.toastr.error('Error al cargar subcategorías')
    });
  }

  obtenerCategorias() {
    this.catService.getCategorias().subscribe({
      next: data => this.categorias = data,
      error: () => this.toastr.error('Error al cargar categorías')
    });
  }

  crearSubcategoria() {
    this.subcatService.crearSubcategoria(this.nuevaSubcategoria).subscribe({
      next: () => {
        this.toastr.success('Subcategoría creada correctamente');
        this.nuevaSubcategoria = { nombre: '', categoria_id: null };
        this.obtenerSubcategorias();
        this.cerrarModalCrear();
      },
      error: () => this.toastr.error('Error al crear subcategoría')
    });
  }

  abrirModalEditar(subcat: any) {
    this.editandoId = subcat.id;
    this.subcategoriaEditada = {
      nombre: subcat.nombre,
      categoria_id: subcat.categoria_id
    };
    this.mostrarModalEditar = true;
  }

  guardarEdicion(id: number) {
    this.subcatService.actualizarSubcategoria(id, this.subcategoriaEditada).subscribe({
      next: () => {
        this.toastr.success('Subcategoría actualizada correctamente');
        this.editandoId = null;
        this.obtenerSubcategorias();
        this.cerrarModalEditar();
      },
      error: () => this.toastr.error('Error al actualizar subcategoría')
    });
  }

  eliminarSubcategoria(id: number) {
    this.subcatService.eliminarSubcategoria(id).subscribe({
      next: () => {
        this.toastr.success('Subcategoría eliminada correctamente');
        this.obtenerSubcategorias();
      },
      error: () => this.toastr.error('Error al eliminar subcategoría')
    });
  }

  activarSubcategoria(id: number) {
    this.subcatService.actualizarSubcategoria(id, { estado: 'activa' }).subscribe({
      next: () => {
        this.toastr.success('Subcategoría activada correctamente');
        this.obtenerSubcategorias();
      },
      error: () => this.toastr.error('Error al activar subcategoría')
    });
  }

  abrirModalCrear() {
    this.mostrarModalCrear = true;
  }
  cerrarModalCrear() {
    this.mostrarModalCrear = false;
  }

  cerrarModalEditar() {
    this.mostrarModalEditar = false;
    this.editandoId = null;
  }
}
