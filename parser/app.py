import navigation.source as src
import navigation.page as page
from navigation.element import PageElementFactory
from config import initialize

    
if __name__ == '__main__':
    initialize()
    rents_list_page = page.RentsListPage(PageElementFactory()) 
    rents_list_page.load()
    #temporary for testing
    while True:
        pass
