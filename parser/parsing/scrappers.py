import navigation.source as src
from bs4 import BeautifulSoup
from dataclasses import dataclass

@dataclass
class AdInfo:
    short_description: str
    district: str
    images: list

class Scrapper:
    def __init__(self):
        self._parser_name = 'html.parser'

    def _get_soup(self, html):
        return BeautifulSoup(html, self._parser_name)

class AdsListScrapper(Scrapper):
    def get_ad_links(self, page):
        links = []
        html = page.get_content()
        soup = self._get_soup(html)

        def is_img(tag):
            attr = tag.attrs.get(src.Attr.SEARCH_START, [])
            return tag.name == src.Tags.SEARCH_START and src.Css.SEARCH_START in attr

        for tag in soup.find_all(is_img):
            url = ''

            if link := tag.find_parent(src.Tags.AD_LINK):
                url = link.attrs.get(src.Attr.AD_LINK, '')

            if url:
                url = f'{src.Urls.DOMAIN}{url}'
                links.append(url)

        return links

class AdScrapper(Scrapper):
    def __init__(self):
        super().__init__()
        self._image_name = ''

    def scrap_info(self, page):
        html = page.get_content()
        soup = self._get_soup(html)
        short_description, district = self._scrap_header(soup)
        images = self._scrap_images(soup)
        return AdInfo(short_description, district, images)

    def _scrap_header(self, soup):
        short_description = soup.find(src.Tags.HEADER)
        text_description = short_description.get_text()

        if not short_description:
            raise Exception('Header not found!')

        district = short_description.next_sibling.contents[0]
        # 2 first words of short description, e.g. "Dvosoban stan"
        tokens = text_description.split(' ')[0:2]
        self._image_name = ' '.join(tokens)
        return (text_description, district.get_text())

    def _scrap_images(self, soup):
        def is_img(tag):
            attr = tag.attrs.get(src.Attr.AD_IMAGE, [])
            return tag.name == src.Tags.AD_IMAGE and self._image_name in attr

        tags = soup.find_all(is_img)

        if not tags:
            raise Exception('No images found!')

        return [tag['src'] for tag in tags]

    def _scrap_details(self):
        pass
