import { MetaInterface } from 'src/interfaces/meta.interface';

export interface PostInterface {
  id: number;
  title: string;
  post_type: string;
  created_at: string;
  updated_at: string;
  relationships: {
    pdf?: PdfInterface;
    link?: LinkInterface;
    html?: HtmlInterface;
  }
}

export interface PdfInterface {
  id: number;
  post_id: number;
  pdf_path: string;
  relationships: Record<string, unknown>
}

export interface LinkInterface {
  id: number;
  post_id: number;
  url: string;
  open_in_new_tab: boolean;
  relationships: Record<string, unknown>
}

export interface HtmlInterface {
  id: number;
  post_id: number;
  description: string;
  html_snippet: string;
  relationships: Record<string, unknown>
}

export interface PostsResultInterface {
  data?: Array<PostInterface>;
  meta?: MetaInterface;
}
