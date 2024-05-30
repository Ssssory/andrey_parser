import navigation.source as src
import navigation.page as page
from navigation.element import PageElementFactory, Popup
from config import initialize

    
if __name__ == '__main__':
    initialize()
    element_factory = PageElementFactory()
    page_factory = page.PageFactory()
    page_enum = page.PagesEnum.RENTS_LIST
    rents_list_page = page_factory.create_page(page_enum) 
    data_popup = element_factory.create_element(src.PopupXPaths.PERSONAL_DATA)
    cookie_popup = element_factory.create_element(src.PopupXPaths.COOKIE_AGREEMENT)
    rents_list_page.register_obsever(data_popup)
    rents_list_page.register_obsever(cookie_popup)
    page_iter = page.PageIterator(2, page_factory, page_enum)
    for page in page_iter:
        page.load()
    #temporary for testing
    while True:
        pass
