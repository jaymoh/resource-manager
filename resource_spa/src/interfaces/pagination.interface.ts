export interface PaginationInterface {
  page: number
  perPage: number
  searchQuery: string
  sortBy: string
  sortDir: sortDirTypes
  includeRelations: string[]
  from: number
  to: number
  total: number
  lastPage: number
}

export type sortDirTypes = 'ASC' | 'DESC'
